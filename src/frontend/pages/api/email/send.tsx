import { NextApiRequest, NextApiResponse  } from 'next';
import axios from 'axios';

interface RequestBodyInterface {
    [key: string]: string | undefined;
    name: string;
    email: string;
    tel?: string;
    message?: string;
}

interface ResponseInterface {
    success: boolean;
    debug?: any;
}

interface MailgunRequestInterface {
    [key: string]: string;
    from: string;
    to: string;
    subject: string;
    text: string;
}

export default async (req: NextApiRequest, res: NextApiResponse<ResponseInterface>) => {

    const input: RequestBodyInterface = req.body;

    const data: MailgunRequestInterface = {
        from: input.email,
        to: 'eden.reich@gmail.com',
        subject: 'Contact Request',
        text: `
            email: ${input.email}
            name: ${input.name}
            tel: ${input.tel}
            message: ${input.message}
        `
    };

    let responseData: ResponseInterface = {
        success: true
    };

    // serialize json to form data...crappy mailgun API don't understand JSON
    let formData: string = '';
    for(const field in data) {
        formData += field+'='+encodeURIComponent(data[field] || '');
        formData += '&';
    }
    if (formData[formData.length-1] == '&') formData = formData.substring(0, formData.length - 1);

    res.setHeader('Content-Type', 'application/json;charset=utf-8');

    try {
        const url: string = `https://api:${process.env.MAILGUN_API_KEY}@api.eu.mailgun.net/v3/${process.env.MAILGUN_DOMAIN}/messages`;
        await axios({url: url, data: formData, method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }});
        res.statusCode = 200;
        res.end(JSON.stringify(responseData));
    } catch (err) {
        responseData.success = false;
        responseData.debug = err;
        res.statusCode = 500;
        res.end(JSON.stringify(responseData));
    }
}

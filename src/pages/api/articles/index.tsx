import { NextApiRequest, NextApiResponse  } from 'next';
import { uuid } from 'uuidv4';
import { Article } from '@/interfaces/article';


interface ResponseInterface {
    success: boolean;
    debug?: any;
}

export default async (req: NextApiRequest, res: NextApiResponse<ResponseInterface>) => {

    const articles: Article[] = [
        {
            id: uuid(),
            title: 'Build your own Kubernetes Cluster (K3S)',
            content: 'Up comming...',
            published_at: 'not published yet',
            updated_at: new Date,
            created_at: new Date
        }
    ];


    return res.end(JSON.stringify(articles));
}

import { NextApiRequest, NextApiResponse } from 'next';
import axios, { AxiosRequestConfig, AxiosResponse } from 'axios';
import getConfig from 'next/config';
import { getClientIpAddress } from '@/utils/visitor';
import { getJWT } from '@/utils/auth';

const { publicRuntimeConfig: { apis: { api } } } = getConfig();

export default async (req: NextApiRequest, res: NextApiResponse) => {
  const ipAddress: string | string[] | null = getClientIpAddress(req);

  if (!ipAddress) {
    console.error(`[api/sessions][${ipAddress}] can't resolve the client ip address`);
    res.status(404).json({});
    return;
  }

  const token: string | null = await getJWT();
  if (!token) {
    return res.status(200).json({});
  }

  try {
    const config: AxiosRequestConfig = {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
    };
    const response: AxiosResponse = await axios.post(`${api.url}/v1/sessions?ip_address=${ipAddress}`, {}, config);
    res.status(200).json(response.data);
  } catch (error) {
    console.error(`[api/sessions] ${JSON.stringify(error)}`);
    res.status(404).json({ message: 'could not create or find the session.' });
  }
};

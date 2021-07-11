import { NextApiRequest, NextApiResponse } from 'next';
import axios, { AxiosRequestConfig, AxiosResponse } from 'axios';
import getConfig from 'next/config';
import { getJWT } from '@/utils/auth';

const { publicRuntimeConfig: { apis: { api } } } = getConfig();

export default async (req: NextApiRequest, res: NextApiResponse) => {
  const token: string | null = await getJWT();
  if (! token) {
    return res.status(200).json({});
  }

  const config: AxiosRequestConfig = {
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
    }
  };
  try {
    const response: AxiosResponse = await axios.post(`${api.url}/v1/notifications`, { ...req.body, is_enabled: true }, config);
    res.status(200).json(response.data);
  } catch (error) {
    console.error(`[api/notifications/add] ${JSON.stringify(error)}`);
    res.status(422).json({ message: 'could not add the session to the notification list.' });
  }
};

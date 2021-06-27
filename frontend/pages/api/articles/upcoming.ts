import { NextApiRequest, NextApiResponse } from 'next';
import axios, { AxiosRequestConfig, AxiosResponse } from 'axios';
import getConfig from 'next/config';
import { getJWT } from '@/utils/auth';

const { publicRuntimeConfig: { apis: { api } } } = getConfig();

export default async (_req: NextApiRequest, res: NextApiResponse) => {
  const token: string | null = await getJWT();
  if (! token) {
    return res.status(200).json([]);
  }

  try {
    const config: AxiosRequestConfig = {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      }
    };
    const response: AxiosResponse = await axios.get(`${api.url}/v1/articles?published_at_null=true`, config);
    res.status(200).json(response.data);
  } catch (error) {
    console.error(`[api/upcoming] ${JSON.stringify(error)}`);
    res.status(404).json({ message: 'could not fetch articles.' });
  }
};

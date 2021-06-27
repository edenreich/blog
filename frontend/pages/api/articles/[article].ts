import { NextApiRequest, NextApiResponse } from 'next';
import axios, { AxiosRequestConfig, AxiosResponse } from 'axios';
import getConfig from 'next/config';
import { getJWT } from '@/utils/auth';

const { publicRuntimeConfig: { apis: { api } } } = getConfig();

export default async (req: NextApiRequest, res: NextApiResponse) => {
  const { article } = req.query;

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
    const response: AxiosResponse = await axios.get(`${api.url}/v1/articles/${article}`, config);
    res.status(200).json(response.data);
  } catch (error) {
    console.error(`[api/article] ${JSON.stringify(error)}`);
    res.status(404).json({ message: 'could not fetch articles.' });
  }
};

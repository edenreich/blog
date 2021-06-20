import { NextApiRequest, NextApiResponse } from 'next';
import axios, { AxiosRequestConfig, AxiosResponse } from 'axios';
import getConfig from 'next/config';
import { getJWT } from '@/utils/auth';

const { publicRuntimeConfig } = getConfig();

export default async (req: NextApiRequest, res: NextApiResponse) => {
  const { session_id } = req.query;
  const token: string = await getJWT();
  if (! token) {
    return res.status(200).json({});
  }

  try {
    const config: AxiosRequestConfig = {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      }
    };
    const response: AxiosResponse = await axios.get(`${publicRuntimeConfig.apis.api.url}/notifications/${session_id}`, config);
    res.status(200).json(response?.data);
  } catch (error) {
    console.error(`[api/notifications] ${error}`);
    res.status(404).json({ message: 'could not find a notification.' });
  }
};
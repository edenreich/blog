import { NextApiRequest, NextApiResponse } from 'next';
import axios, { AxiosRequestConfig, AxiosResponse } from 'axios';
import getConfig from 'next/config';
import { INotification } from '@/interfaces/notification';
import { getJWT } from '@/utils/auth';

const { publicRuntimeConfig: { apis: { api } } } = getConfig();

export default async (req: NextApiRequest, res: NextApiResponse) => {
  const { session } = req.body;

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
  let notification: INotification;
  try {
    const response: AxiosResponse = await axios.get(`${api.url}/v1/notifications/${session}`, config);
    notification = response.data;
  } catch (error) {
    console.error(`[api/notifications] ${JSON.stringify(error)}`);
    res.status(404).json({ message: 'could not find the notification.' });
    return;
  }

  try {
    const response: AxiosResponse = await axios.put(`${api.url}/v1/notifications/${notification.uuid}`, {...req.body, is_enabled: false }, config);
    res.status(200).json(response.data);
  } catch (error) {
    console.error(`[api/notifications] ${JSON.stringify(error)}`);
    res.status(404).json({ message: 'could not disable the notification.' });
  }
};

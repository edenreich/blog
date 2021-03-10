import { NextApiRequest, NextApiResponse } from 'next';
import axios, { AxiosResponse } from 'axios';
import getConfig from 'next/config';
import { INotification } from '@/interfaces/notification';

const { publicRuntimeConfig } = getConfig();

export default async (req: NextApiRequest, res: NextApiResponse) => {
  const { session } = req.body;

  let notification: INotification;
  try {
    const response: AxiosResponse = await axios.get(`${publicRuntimeConfig.apis.admin.url}/notifications/${session}`, { headers: { 'Content-Type': 'application/json' } });
    notification = response.data;
  } catch (error) {
    console.error(`[api/notifications] ${JSON.stringify(error)}`);
    res.status(404).json({ message: 'could not find the notification.' });
    return;
  }

  try {
    const response: AxiosResponse = await axios.put(`${publicRuntimeConfig.apis.admin.url}/notifications/${notification.uuid}`, {...req.body, is_enabled: false }, { headers: { 'Content-Type': 'application/json' } });
    res.status(200).json(response.data);
  } catch (error) {
    console.error(`[api/notifications] ${JSON.stringify(error)}`);
    res.status(404).json({ message: 'could not disable the notification.' });
  }
};

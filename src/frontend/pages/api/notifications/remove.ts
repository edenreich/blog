import { NextApiRequest, NextApiResponse } from 'next';
import axios, { AxiosResponse } from 'axios';
import getConfig from 'next/config';
import Cors from 'cors';
import { runMiddleware } from '@/utils/middleware';
import { INotification } from '@/interfaces/notification';

const { publicRuntimeConfig } = getConfig();

const cors = Cors({
  methods: ['GET', 'OPTIONS', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE'],
  origin: /http(s)?:\/\/(.+\.)?eden-reich\.com(:\d{1,5})?$/,
});

export default async (req: NextApiRequest, res: NextApiResponse) => {
  await runMiddleware(req, res, cors);
  const { session } = req.body;

  let notification: INotification;
  try {
    const response: AxiosResponse = await axios.get(`${publicRuntimeConfig.apis.admin.url}/notifications/${session}`, { headers: { 'Content-Type': 'application/json' } });
    notification = response.data;
  } catch (error) {
    console.error(error);
    res.status(404).json({ message: 'could not find the notification.' });
    return;
  }

  try {
    const response: AxiosResponse = await axios.put(`${publicRuntimeConfig.apis.admin.url}/notifications/${notification.uuid}`, {...req.body, is_enabled: false }, { headers: { 'Content-Type': 'application/json' } });
    res.status(200).json(response.data);
  } catch (error) {
    console.error(error);
    res.status(404).json({ message: 'could not disable the notification.' });
  }
};

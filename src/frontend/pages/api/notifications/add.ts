import { NextApiRequest, NextApiResponse } from 'next';
import axios, { AxiosResponse } from 'axios';
import getConfig from 'next/config';

const { publicRuntimeConfig } = getConfig();

export default async (req: NextApiRequest, res: NextApiResponse) => {
  try {
    const response: AxiosResponse = await axios.post(`${publicRuntimeConfig.apis.admin.url}/notifications`, { ...req.body, is_enabled: true }, { headers: { 'Content-Type': 'application/json' } });
    res.status(200).json(response.data);
  } catch (error) {
    console.error(`[api/notifications/add] ${JSON.stringify(error)}`);
    res.status(404).json({ message: 'could not add the session to the notification list.' });
  }
};

import { NextApiRequest, NextApiResponse } from 'next';
import axios, { AxiosResponse } from 'axios';
import getConfig from 'next/config';
import Cors from 'cors';
import { runMiddleware } from '@/utils/middleware';

const { publicRuntimeConfig } = getConfig();

const cors = Cors({
  methods: ['GET', 'OPTIONS', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE'],
  origin: /http(s)?:\/\/(.+\.)?eden-reich\.com(:\d{1,5})?$/,
});

export default async (req: NextApiRequest, res: NextApiResponse) => {
  await runMiddleware(req, res, cors);
  const { session_id } = req.query;
  try {
    const response: AxiosResponse = await axios.get(`${publicRuntimeConfig.apis.admin.url}/notifications/${session_id}`, { headers: { 'Content-Type': 'application/json' } });
    res.status(200).json(response.data);
  } catch (error) {
    console.error(error.response.data);
    res.status(404).json({ message: 'could not find a notification.' });
  }
};

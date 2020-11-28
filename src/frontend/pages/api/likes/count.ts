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

  try {
    const response: AxiosResponse = await axios.get(`${publicRuntimeConfig.apis.default.url}/likes/count?article=${req.query.article}`);
    res.status(200).json(response.data);
  } catch (error) {
    console.error(error);
    res.status(error.response.status).json(error.response.data);
  }
};

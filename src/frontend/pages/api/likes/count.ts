import { NextApiRequest, NextApiResponse } from 'next';
import axios, { AxiosResponse } from 'axios';
import getConfig from 'next/config';

const { publicRuntimeConfig } = getConfig();

export default async (req: NextApiRequest, res: NextApiResponse) => {
  try {
    const response: AxiosResponse = await axios.get(`${publicRuntimeConfig.apis.default.url}/likes/count?article=${req.query.article}`);
    res.status(200).json(response.data);
  } catch (error) {
    console.error(error);
    res.status(error.response.status).json(error.response.data);
  }
};

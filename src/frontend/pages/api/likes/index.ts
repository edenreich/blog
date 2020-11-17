import { NextApiRequest, NextApiResponse } from 'next';
import axios, { AxiosResponse } from 'axios';
import getConfig from 'next/config';

const { publicRuntimeConfig } = getConfig();

export default async (req: NextApiRequest, res: NextApiResponse) => {
  const response: AxiosResponse = await axios.post(`${publicRuntimeConfig.apis.default.url}/likes`, req.body);
  res.status(200).json(response.data);
};

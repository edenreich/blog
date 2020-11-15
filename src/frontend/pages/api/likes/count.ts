import { NextApiRequest, NextApiResponse } from 'next';
import axios, { AxiosRequestConfig, AxiosResponse } from 'axios';
import getConfig from 'next/config';

const { publicRuntimeConfig } = getConfig();

let axiosConfig: AxiosRequestConfig = {};
if (publicRuntimeConfig.app.env === 'development') {
  process.env.NODE_TLS_REJECT_UNAUTHORIZED = '0';
  axiosConfig = {
    headers: {
      host: publicRuntimeConfig.apis.default.hostname,
    },
  };
}

export default async (req: NextApiRequest, res: NextApiResponse) => {
  const response: AxiosResponse = await axios.get(
    `${publicRuntimeConfig.apis.default.url}/likes/count?article=${req.query.article}`,
    axiosConfig,
  );
  res.status(200).json(response.data);
};

import { NextApiRequest, NextApiResponse  } from 'next';
import axios, { AxiosResponse, AxiosRequestConfig } from 'axios';
import getConfig from 'next/config';

const { publicRuntimeConfig } = getConfig();

export default async (req: NextApiRequest, res: NextApiResponse) => {
  const axiosConfig: AxiosRequestConfig = { 
    headers: { 
      host: publicRuntimeConfig.apis.default.hostname
    } 
  };

  if (publicRuntimeConfig.app.env === 'development') {
    process.env.NODE_TLS_REJECT_UNAUTHORIZED = '0';
  }

  const response: AxiosResponse = await axios.get(`${publicRuntimeConfig.apis.default.ip}/likes/count?article=${req.query.article}`, axiosConfig);
  res.status(200).json(response.data);
};

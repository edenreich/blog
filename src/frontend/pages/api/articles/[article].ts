import { NextApiRequest, NextApiResponse } from 'next';
import axios, { AxiosResponse } from 'axios';
import getConfig from 'next/config';

const { publicRuntimeConfig } = getConfig();

export default async (req: NextApiRequest, res: NextApiResponse) => {
  const { article } = req.query;
  try {
    const response: AxiosResponse = await axios.get(`${publicRuntimeConfig.apis.admin.url}/articles/${article}`, { headers: { 'Content-Type': 'application/json' } });
    res.status(200).json(response.data);
  } catch (error) {
    console.error(error);
    res.status(404).json({ message: 'could not fetch articles.' });
  }
};

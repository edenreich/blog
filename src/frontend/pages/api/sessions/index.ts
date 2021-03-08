import { NextApiRequest, NextApiResponse } from 'next';
import axios, { AxiosResponse } from 'axios';
import getConfig from 'next/config';
import { getClientIpAddress } from '@/utils/visitor';

const { publicRuntimeConfig } = getConfig();

export default async (req: NextApiRequest, res: NextApiResponse) => {
  const ipAddress = getClientIpAddress(req);

  if (!ipAddress) {
    console.error('can\'t resolve the client ip address');
    res.status(404).json({});
    return;
  }

  try {
    const response: AxiosResponse = await axios.post(`${publicRuntimeConfig.apis.admin.url}/sessions`, { ip_address: ipAddress }, { headers: { 'Content-Type': 'application/json' } });
    res.status(200).json(response.data);
  } catch (error) {
    console.error(error);
    res.status(404).json({ message: 'could not create or find the session.' });
  }
};

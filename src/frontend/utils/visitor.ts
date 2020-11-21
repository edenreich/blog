import { NextApiRequest } from 'next';

export const getClientIpAddress = (req: NextApiRequest) => {
  return req.headers['x-real-ip'] || req.headers['x-forwarded-for'];
};

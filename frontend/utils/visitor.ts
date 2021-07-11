import { NextApiRequest } from 'next';

export const getClientIpAddress = (req: NextApiRequest): string | string[] | null => {
  return req?.headers['x-real-ip'] || req?.headers['x-forwarded-for'];
};

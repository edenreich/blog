import getConfig from 'next/config';

const { publicRuntimeConfig: { app: { assets_url } } } = getConfig();

export const asset = (path: string): string => {
  return `${assets_url}/${path.replace(/^\//, '')}`;
};

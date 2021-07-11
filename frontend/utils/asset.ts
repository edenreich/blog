import getConfig from 'next/config';

const { publicRuntimeConfig: { app: { assets_url } } } = getConfig();

export const asset = (path: string | undefined): string => {
  if (!path) {
    return '';
  }
  return `${assets_url}/${path?.replace(/^\//, '')}`;
};

import axios, { AxiosRequestConfig, AxiosResponse } from 'axios';
import getConfig from 'next/config';

const { serverRuntimeConfig: { apiCredentials }, publicRuntimeConfig: { apis: { authentication } } } = getConfig();

export const getJWT = async (): Promise<string | null> => {
  const config: AxiosRequestConfig = {
    headers: {
        'Content-Type': 'application/json'
    }
  };
  try {
    const response: AxiosResponse = await axios.post(`${authentication.url}/authentication/jwt`, apiCredentials, config);
    return response.data.token;
  } catch (error) {
    console.error(`[utils][getJWT] could not fetch access token!`, error);
    return null;
  }
};

import axios, { AxiosRequestConfig, AxiosResponse } from 'axios';
import getConfig from 'next/config';

const { serverRuntimeConfig: { apiCredentials }, publicRuntimeConfig: { apis: { api } } } = getConfig();

export const getJWT = async (): Promise<string> => {
  const config: AxiosRequestConfig = {
    headers: {
        'Content-Type': 'application/json'
    }
  };
  try {
    const response: AxiosResponse = await axios.post(`${api.url}/authorize`, apiCredentials, config);
    return response.data.token;
  } catch (error) {
    console.error(`[utils][getJWT] could not fetch access token!`);
    return null;
  }
};

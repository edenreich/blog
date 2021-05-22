import { server } from '../src/server';
import request from 'supertest';

describe('GET /api/healthcheck', () => {
  it('Returns 200 with status OK', async (done) => {
    const response = await request(server.callback()).get('/api/healthcheck');
    expect(response.body).toEqual({ status: 'OK' });
    expect(response.statusCode).toEqual(200);
    done();
  });
});

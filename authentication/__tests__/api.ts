import { server } from '@app/server';
import request from 'supertest';

describe('GET /api/healthcheck', () => {
  it('Returns 200 with status OK', async (done) => {
    const response = await request(server.callback()).get('/api/healthcheck');
    expect(response.body).toEqual({ status: 'OK.' });
    expect(response.statusCode).toEqual(200);
    done();
  });
});

describe('POST /api/jwt and /api/authorize', () => {
  it('Returns 401 for invalid credentials', async (done) => {
    const response = await request(server.callback())
      .post('/api/jwt')
      .send({ username: 'invalid', password: 'invalid' });
    expect(response.body.error).toBeDefined();
    expect(response.status).toEqual(401);
    done();
  });

  it('Returns 200 with a JWT if credentials are valid', async (done) => {
    const response = await request(server.callback())
      .post('/api/jwt')
      .send({ username: 'test', password: 'somepassword' });
    expect(response.body.token).toBeDefined();
    expect(response.status).toEqual(200);
    done();
  });

  it('Can authenticate using the fetched JWT', async (done) => {
    const response = await request(server.callback())
      .post('/api/jwt')
      .send({ username: 'test', password: 'somepassword' });
    const jwt: string = response.body.token; 
    const response2 = await request(server.callback())
      .post('/api/authorize')
      .set('Authorization', `Bearer ${jwt}`);
    expect(response2.status).toEqual(200);
    done();
  });

  it('Throw an http error if request without content type application/json', async (done) => {
    const response = await request(server.callback())
      .post('/api/jwt')
      .set('Content-Type', 'application/x-www-form-urlencoded')
      .send({ username: 'test', password: 'somepassword' });
    expect(response.status).toEqual(422);
    done();
  });
});

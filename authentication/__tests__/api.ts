import { server } from '@app/server';
import request from 'supertest';
import { connection } from '@app/database/connection';
import { IUser, User } from '@app/entities/User';
import { Connection } from 'typeorm';
import { IRole, Role } from '@app/entities/Role';

describe('GET /api/authentication/healthcheck', () => {
  it('Returns 200 with status OK', async (done): Promise<void> => {
    const response = await request(server.callback()).get('/api/authentication/healthcheck');
    expect(response.body).toEqual({ status: 'OK.' });
    expect(response.statusCode).toEqual(200);
    done();
  });
});

describe('POST /api/authentication/jwt and /api/authentication/authorize', () => {
  let user: IUser;
  let conn: Connection;

  beforeAll(async (done): Promise<void> => {
    conn = await connection;
    await conn.manager.query('TRUNCATE TABLE users CASCADE;');
    await conn.manager.query('TRUNCATE TABLE roles CASCADE;');
    const role: IRole = await conn.manager.save(new Role('USER_ROLE'));
    const role2: IRole = await conn.manager.save(new Role('ADMIN_ROLE'));
    user = new User();
    user.username = 'test';
    user.password = 'somepassword';
    user.roles = [
      role,
      role2,
    ];
    user = await conn.manager.save(user);
    done();
  });

  afterAll(async (done): Promise<void> => {
    conn.close();
    done();
  });

  it('Returns 401 for invalid credentials', async (done): Promise<void> => {
    const response = await request(server.callback())
      .post('/api/authentication/jwt')
      .send({username: 'invalid', password: 'invalid'});
    expect(response.body.error).toBeDefined();
    expect(response.status).toEqual(401);
    done();
  });

  it('Returns 200 with a JWT if credentials are valid', async (done): Promise<void> => {
    const response = await request(server.callback())
      .post('/api/authentication/jwt')
      .send({username: 'test', password: 'somepassword'});
    expect(response.body.token).toBeDefined();
    expect(response.status).toEqual(200);
    done();
  });

  it('Can authenticate using the fetched JWT', async (done): Promise<void> => {
    const response = await request(server.callback())
      .post('/api/authentication/jwt')
      .send({username: 'test', password: 'somepassword'});
    const jwt: string = response.body.token;
    const response2 = await request(server.callback())
      .post('/api/authentication/authorize')
      .set('Authorization', `Bearer ${jwt}`);
    expect(response2.status).toEqual(200);
    done();
  });

  it('Throw an http error if request without content type application/json', async (done): Promise<void> => {
    const response = await request(server.callback())
      .post('/api/authentication/jwt')
      .set('Content-Type', 'application/x-www-form-urlencoded')
      .send({ username: 'test', password: 'somepassword' });
    expect(response.status).toEqual(422);
    done();
  });
});

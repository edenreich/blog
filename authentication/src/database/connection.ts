import 'reflect-metadata';
import { Connection, createConnection } from 'typeorm';
import connectionOptions from './ormconfig';

export const connection: Promise<Connection> = createConnection(connectionOptions);

import { Entity, Column, PrimaryGeneratedColumn } from 'typeorm';

type AvailableRoles = 'USER_ROLE' | 'ADMIN_ROLE';

export interface IRole {
  id?: string;
  name: AvailableRoles;
}

@Entity({name: 'roles'})
export class Role {

  constructor(name: AvailableRoles) {
    this.name = name;
  }

  @PrimaryGeneratedColumn('uuid')
  id: string;

  @Column({unique: true})
  name: AvailableRoles;

}

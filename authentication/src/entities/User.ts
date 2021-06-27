import { Entity, Column, PrimaryGeneratedColumn, ManyToMany, JoinTable, BeforeInsert } from 'typeorm';
import { IRole, Role } from './Role';
import { hash } from 'bcryptjs';

export interface IUser {
  id?: string;
  username: string;
  password: string;
  roles: IRole[];
}

@Entity({name: 'users'})
export class User implements IUser {

  @BeforeInsert()
  async encryptPassword() {
    this.password = await hash(this.password, 10);
  }

  @PrimaryGeneratedColumn('uuid')
  id: string;

  @Column()
  username: string;

  @Column()
  password: string;

 @ManyToMany(_type => Role, role => role.id, { eager: true, cascade: true, onDelete: 'CASCADE' })
  @JoinTable({
    name: 'users_roles',
    joinColumns: [
      { name: 'user_id' }
    ],
    inverseJoinColumns: [
      { name: 'role_id' }
    ]
  })
  roles: IRole[];

}

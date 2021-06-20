
export interface IRole {
  id: string;
  name: 'USER_ROLE' | 'ADMIN_ROLE';
}

export interface IUser {
  id: string;
  username: string;
  roles: IRole[];
}

export interface IAuthenticateableDTO {
  username: string;
  password: string;
}

export interface IAuthenticatedUser {
  data: IUser;
  iat: number;
  exp: number;
}

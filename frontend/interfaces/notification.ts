export interface INotification {
  id: number;
  uuid: string;
  session: string;
  email: string;
  is_enabled: boolean;
  updated_at: Date;
  created_at: Date;
}

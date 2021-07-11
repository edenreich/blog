export interface INotification {
  id: number;
  session: string;
  email: string;
  is_enabled: boolean;
  updated_at: Date;
  created_at: Date;
}

// You can include shared interfaces/types in a separate file
// and then use them in any component by importing them. For
// example, to import the interface below do:
//
// import User from 'path/to/interfaces';

export interface Article {
  id: string;
  title: string;
  content: string;
  link: string;
  published_at: string;
  updated_at: Date;
  created_at: Date;
}
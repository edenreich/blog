// You can include shared interfaces/types in a separate file
// and then use them in any component by importing them. For
// example, to import the interface below do:
//
// import User from 'path/to/interfaces';

declare type Thumbnail = {
  hash: string;
  ext: string;
  mime: string;
  width: number;
  height: number;
  path: string | null;
  url: string;
};

declare type Small = {
  hash: string;
  ext: string;
  mime: string;
  width: number;
  height: number;
  path: string | null;
  url: string;
};

declare type Formats = {
  thumbnail: Thumbnail;
  small: Small;
};

declare type ArticleThumbnail = {
  id: number;
  name: string;
  alternativeText: string;
  caption: string;
  width: number;
  height: number;
  formats: Formats;
  hash: string;
  ext: string;
  mime: string;
  size: number;
  url: string;
  previewUrl: string | null;
  provider: string;
  provider_metadata: string | null;
  created_at: Date;
  updated_at: Date;
};

declare type Like = {
  id: number;
  uuid: string;
  reaction_type: string;
  ip_address: string;
};

export interface Article {
  id: string;
  title: string;
  slug: string;
  meta_keywords: string;
  meta_description: string;
  meta_thumbnail: ArticleThumbnail;
  content: string;
  published: boolean;
  likes: Like[];
  published_at: Date;
  updated_at: Date;
  created_at: Date;
}

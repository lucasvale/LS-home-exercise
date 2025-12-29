export interface Person {
  uid: string;
  name: string;
  url: string;
  description: string | null;
  created: string | null;
  edited: string | null;
  gender: string | null;
  skin_color: string | null;
  hair_color: string | null;
  height: string | null;
  eye_color: string | null;
  mass: string | null;
  homeworld: string | null;
  birth_year: string | null;
  vehicles: string[] | null;
  starships: string[] | null;
  films: string[] | null;
}

export interface Film {
  uid: string;
  description: string;
  created: string;
  edited: string;
  starships: string[];
  vehicles: string[];
  planets: string[];
  producer: string;
  title: string;
  episode_id: number;
  director: string;
  release_date: string;
  opening_crawl: string;
  characters: string[];
  species: string[];
  url: string;
}

export interface SearchResult {
  id: string;
  name: string;
}

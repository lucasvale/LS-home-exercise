import type { Person, Film, SearchResult } from '@/types/api';

const BASE_URL = 'http://localhost:8000/api';

interface ApiResponse<T> {
  status: 'success' | 'fail';
  data: T;
  meta?: {
    total: number;
    pages: number;
    previous: string | null;
    next: string | null;
  };
}

async function fetchApi<T>(url: string): Promise<T> {
  const response = await fetch(url);
  const json: ApiResponse<T> = await response.json();

  if (!response.ok || json.status === 'fail') {
    throw new Error(`API error! status: ${response.status}`);
  }

  return json.data;
}

export async function searchPeople(name: string): Promise<SearchResult[]> {
  const data = await fetchApi<Person[]>(`${BASE_URL}/people?name=${encodeURIComponent(name)}`);
  return data.map(person => ({
    id: person.uid,
    name: person.name
  }));
}

export async function searchFilms(title: string): Promise<SearchResult[]> {
  const data = await fetchApi<Film[]>(`${BASE_URL}/films?title=${encodeURIComponent(title)}`);
  return data.map(film => ({
    id: film.uid,
    name: film.title
  }));
}

export async function getPersonById(id: string): Promise<Person> {
  return fetchApi<Person>(`${BASE_URL}/people/${id}`);
}

export async function getFilmById(id: string): Promise<Film> {
  return fetchApi<Film>(`${BASE_URL}/films/${id}`);
}

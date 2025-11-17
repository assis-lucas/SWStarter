import { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import api from '../services/api';
import Card from '../components/base/Card';
import CardItem from '../components/base/CardItem';
import Button from '../components/base/Button';
import Loading from '../components/base/Loading';
import EmptyState from '../components/base/EmptyState';
import EntityDetail from '../components/base/EntityDetail';
import { formatDate } from '../utils/format';
import { ArrowLeftIcon } from '@heroicons/react/24/solid';

const FilmDetails = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const [film, setFilm] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchFilmDetails();
  }, [id]);

  const fetchFilmDetails = async () => {
    setLoading(true);
    try {
      const response = await api.get(`/films/${id}`);
      setFilm(response.data.data);
    } catch (error) {
      console.error('Error fetching film details:', error);
      if (error.response?.status === 404) {
        navigate('/');
      }
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <Card className="flex flex-col md:max-w-[780px] mx-auto">
        <Loading message="Loading movie details..." />
      </Card>
    );
  }

  if (!film) {
    return (
      <Card className="flex flex-col md:max-w-[780px] mx-auto">
        <EmptyState>
          <h2 className="font-bold">Movie not found.</h2>
        </EmptyState>
      </Card>
    );
  }

  return (
    <div className="md:max-w-[780px] mx-auto space-y-4 sm:space-y-6">
      <Button className="flex items-center gap-2 text-sm sm:text-base" variant="primary" onClick={() => navigate('/')}>
        <ArrowLeftIcon className="w-4 h-4 sm:w-5 sm:h-5" />
        Back
      </Button>

      <Card>
        <h1 className="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">{film.title}</h1>
        <p className="text-base sm:text-lg text-gray-600 mb-6">Episode {film.episode_id}</p>

        <div className="mb-6 sm:mb-8">
          <h2 className="text-lg sm:text-xl font-semibold text-gray-800 mb-3">Opening Crawl</h2>
          <p className="text-gray-700 leading-relaxed italic">{film.opening_crawl}</p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
          <div>
            <h2 className="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Film Information</h2>
            <dl className="space-y-2 text-sm sm:text-base">
              <div className="flex">
                <dt className="font-medium text-gray-600 w-24 sm:w-32">Director:</dt>
                <dd className="text-gray-900">{film.director}</dd>
              </div>
              <div className="flex">
                <dt className="font-medium text-gray-600 w-24 sm:w-32">Producer:</dt>
                <dd className="text-gray-900">{film.producer}</dd>
              </div>
              <div className="flex">
                <dt className="font-medium text-gray-600 w-24 sm:w-32">Release Date:</dt>
                <dd className="text-gray-900">{formatDate(film.release_date)}</dd>
              </div>
            </dl>
          </div>
        </div>

        <EntityDetail
          title="Characters"
          items={film.characters}
          renderItem={(character, onClick) => (
            <CardItem
              key={character.id}
              className="bg-gray-50 hover:shadow-lg transition-shadow cursor-pointer"
              onClick={() => onClick(character.id)}
            >
              <h3 className="font-semibold text-gray-900">{character.name}</h3>
              <p className="text-sm text-gray-600 mt-1">Gender: {character.gender}</p>
              <p className="text-sm text-gray-600">Birth Year: {character.birth_year}</p>
              <p className="text-sm text-primary-600 mt-2">Click to view details →</p>
            </CardItem>
          )}
          onItemClick={(id) => navigate(`/person/${id}`)}
        />

        <EntityDetail
          title="Planets"
          items={film.planets}
          renderItem={(planet) => (
            <CardItem key={planet.id} className="bg-gray-50">
              <h3 className="font-semibold text-gray-900">{planet.name}</h3>
              <p className="text-sm text-gray-600 mt-1">Climate: {planet.climate}</p>
              <p className="text-sm text-gray-600">Terrain: {planet.terrain}</p>
              <p className="text-sm text-gray-600">Population: {planet.population}</p>
            </CardItem>
          )}
        />

        <EntityDetail
          title="Starships"
          items={film.starships}
          renderItem={(starship) => (
            <CardItem key={starship.id} className="bg-gray-50">
              <h3 className="font-semibold text-gray-900">{starship.name}</h3>
              <p className="text-sm text-gray-600 mt-1">Model: {starship.model}</p>
              <p className="text-sm text-gray-600">Class: {starship.starship_class}</p>
            </CardItem>
          )}
        />

        <EntityDetail
          title="Vehicles"
          items={film.vehicles}
          renderItem={(vehicle) => (
            <CardItem key={vehicle.id} className="bg-gray-50">
              <h3 className="font-semibold text-gray-900">{vehicle.name}</h3>
              <p className="text-sm text-gray-600 mt-1">Model: {vehicle.model}</p>
              <p className="text-sm text-gray-600">Class: {vehicle.vehicle_class}</p>
            </CardItem>
          )}
        />

        <EntityDetail
          title="Species"
          items={film.species}
          renderItem={(specie) => (
            <CardItem key={specie.id} className="bg-gray-50">
              <h3 className="font-semibold text-gray-900">{specie.name}</h3>
              <p className="text-sm text-gray-600 mt-1">Classification: {specie.classification}</p>
              <p className="text-sm text-gray-600">Language: {specie.language}</p>
            </CardItem>
          )}
        />
      </Card>
    </div>
  );
};

export default FilmDetails;

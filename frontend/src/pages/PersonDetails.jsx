import { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import api from '../services/api';
import Card from '../components/base/Card';
import CardItem from '../components/base/CardItem';
import Button from '../components/base/Button';
import Loading from '../components/base/Loading';
import EmptyState from '../components/base/EmptyState';
import EntityDetail from '../components/base/EntityDetail';
import { ArrowLeftIcon } from '@heroicons/react/24/solid';
import { formatDate } from '../utils/format';

const PersonDetails = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const [person, setPerson] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchPersonDetails();
  }, [id]);

  const fetchPersonDetails = async () => {
    setLoading(true);
    try {
      const response = await api.get(`/people/${id}`);
      setPerson(response.data.data);
    } catch (error) {
      console.error('Error fetching person details:', error);
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
        <Loading message="Loading person details..." />
      </Card>
    );
  }

  if (!person) {
    return (
      <Card className="flex flex-col md:max-w-[780px] mx-auto">
        <EmptyState>
          <h2 className="font-bold">Person not found</h2>
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
        <h1 className="text-2xl sm:text-3xl font-bold text-gray-900 mb-6">{person.name}</h1>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
          <div>
            <h2 className="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Personal Information</h2>
            <dl className="space-y-2 text-sm sm:text-base">
              <div className="flex">
                <dt className="font-medium text-gray-600 w-24 sm:w-32">Birth Year:</dt>
                <dd className="text-gray-900">{person.birth_year}</dd>
              </div>
              <div className="flex">
                <dt className="font-medium text-gray-600 w-24 sm:w-32">Gender:</dt>
                <dd className="text-gray-900 capitalize">{person.gender}</dd>
              </div>
              <div className="flex">
                <dt className="font-medium text-gray-600 w-24 sm:w-32">Height:</dt>
                <dd className="text-gray-900">{person.height} cm</dd>
              </div>
              <div className="flex">
                <dt className="font-medium text-gray-600 w-24 sm:w-32">Mass:</dt>
                <dd className="text-gray-900">{person.mass} kg</dd>
              </div>
            </dl>
          </div>

          <div>
            <h2 className="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Physical Appearance</h2>
            <dl className="space-y-2 text-sm sm:text-base">
              <div className="flex">
                <dt className="font-medium text-gray-600 w-24 sm:w-32">Hair Color:</dt>
                <dd className="text-gray-900 capitalize">{person.hair_color}</dd>
              </div>
              <div className="flex">
                <dt className="font-medium text-gray-600 w-24 sm:w-32">Skin Color:</dt>
                <dd className="text-gray-900 capitalize">{person.skin_color}</dd>
              </div>
              <div className="flex">
                <dt className="font-medium text-gray-600 w-24 sm:w-32">Eye Color:</dt>
                <dd className="text-gray-900 capitalize">{person.eye_color}</dd>
              </div>
            </dl>
          </div>
        </div>

        {person.homeworld && (
          <div className="mb-6 sm:mb-8">
            <h2 className="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Homeworld</h2>
            <CardItem className="bg-gray-50">
              <h3 className="font-semibold text-lg text-gray-900">{person.homeworld.name}</h3>
              <p className="text-sm text-gray-600 mt-1">Climate: {person.homeworld.climate}</p>
              <p className="text-sm text-gray-600">Terrain: {person.homeworld.terrain}</p>
            </CardItem>
          </div>
        )}

        <EntityDetail
          title="Movies"
          items={person.films}
          renderItem={(film, onClick) => (
            <CardItem
              key={film.id}
              className="bg-gray-50 hover:shadow-lg transition-shadow cursor-pointer"
              onClick={() => onClick(film.id)}
            >
              <h3 className="font-semibold text-gray-900 line-clamp-1">{film.title}</h3>
              <p className="text-sm text-gray-600 mt-1">Episode {film.episode_id}</p>
              <p className="text-sm text-gray-600">{formatDate(film.release_date)}</p>
              <p className="text-sm text-primary-600 mt-2">Click to view details →</p>
            </CardItem>
          )}
          onItemClick={(id) => navigate(`/film/${id}`)}
        />

        <EntityDetail
          title="Species"
          items={person.species}
          renderItem={(specie) => (
            <CardItem key={specie.id} className="bg-gray-50">
              <h3 className="font-semibold text-gray-900">{specie.name}</h3>
              <p className="text-sm text-gray-600 mt-1">Classification: {specie.classification}</p>
              <p className="text-sm text-gray-600">Language: {specie.language}</p>
            </CardItem>
          )}
        />

        <EntityDetail
          title="Starships"
          items={person.starships}
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
          items={person.vehicles}
          renderItem={(vehicle) => (
            <CardItem key={vehicle.id} className="bg-gray-50">
              <h3 className="font-semibold text-gray-900">{vehicle.name}</h3>
              <p className="text-sm text-gray-600 mt-1">Model: {vehicle.model}</p>
              <p className="text-sm text-gray-600">Class: {vehicle.vehicle_class}</p>
            </CardItem>
          )}
        />
      </Card>
    </div>
  );
};

export default PersonDetails;

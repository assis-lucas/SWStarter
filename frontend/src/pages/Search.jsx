import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { Bars3Icon, Squares2X2Icon } from '@heroicons/react/24/outline';
import api from '../services/api';
import Card from '../components/base/Card';
import Button from '../components/base/Button';
import Input from '../components/base/Input';
import Radio from '../components/base/Radio';
import Pagination from '../components/base/Pagination';
import Record from '../components/base/Record';
import Loading from '../components/base/Loading';
import EmptyState from '../components/base/EmptyState';
import { getFromLocalStorage, setToLocalStorage } from '../utils/localStorage';

const ALLOWED_VIEW_MODES = ['detailed', 'grid'];
const VIEW_MODE_STORAGE_KEY = 'search_view_mode';

const Search = () => {
  const [searchType, setSearchType] = useState('people');
  const [searchQuery, setSearchQuery] = useState('');
  const [results, setResults] = useState([]);
  const [loading, setLoading] = useState(false);
  const [meta, setMeta] = useState(null);
  const [viewMode, setViewMode] = useState(() =>
    getFromLocalStorage(VIEW_MODE_STORAGE_KEY, 'detailed', ALLOWED_VIEW_MODES)
  );
  const navigate = useNavigate();

  useEffect(() => {
    fetchResults();
  }, [searchType]);

  useEffect(() => {
    setToLocalStorage(VIEW_MODE_STORAGE_KEY, viewMode, ALLOWED_VIEW_MODES);
  }, [viewMode]);

  const fetchResults = async (page = 1) => {
    setLoading(true);
    try {
      const endpoint = searchType === 'people' ? '/people' : '/films';
      const params = { page };
      if (searchQuery) {
        params.search = searchQuery;
      }

      const response = await api.get(endpoint, { params });
      setResults(response.data.data);
      setMeta(response.data.meta);
    } catch (error) {
      console.error('Error fetching results:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleSearch = (e) => {
    e.preventDefault();
    fetchResults();
  };

  const handleViewDetails = (id) => {
    const route = searchType === 'people' ? `/person/${id}` : `/film/${id}`;
    navigate(route);
  };

  return (
    <div className="flex items-start justify-center gap-6">
      <Card className="sm:w-[410px] space-y-4">
        <h2 className="text-sm font-semibold text-gray-800">What are you searching for?</h2>

        <div className="flex items-center gap-4">
          <Radio
            label="People"
            value="people"
            checked={searchType === 'people'}
            onChange={(e) => setSearchType(e.target.value)}
            name="searchType"
          />
          <Radio
            label="Movies"
            value="films"
            checked={searchType === 'films'}
            onChange={(e) => setSearchType(e.target.value)}
            name="searchType"
          />
        </div>

        <form onSubmit={handleSearch} className="space-y-4">
          <div>
            <Input
              placeholder={searchType === 'people' ? 'e.g, Chewbacca, Yoda, Boba Fett' : 'e.g, A New Hope, The Empire Strikes Back'}
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
            />
          </div>
          <Button type="submit" className="w-full" disabled={loading || !searchQuery.trim()}>
            {loading ? 'Searching...' : 'Search'}
          </Button>
        </form>
      </Card>

      <Card className="sm:w-[580px] flex flex-col">
        <div className="flex justify-between items-center mb-4 border-b pb-4 border-gray-300">
          <h2 className="text-xl font-bold text-gray-800">Results</h2>
          <div className="flex gap-2">
            <button
              onClick={() => setViewMode('detailed')}
              className={`p-2 rounded-lg border transition-colors ${viewMode === 'detailed'
                ? 'bg-primary-600 text-white border-primary-600'
                : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50'
                }`}
              title="Detailed view"
            >
              <Bars3Icon className="w-5 h-5" />
            </button>
            <button
              onClick={() => setViewMode('grid')}
              className={`p-2 rounded-lg border transition-colors ${viewMode === 'grid'
                ? 'bg-primary-600 text-white border-primary-600'
                : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50'
                }`}
              title="Grid view"
            >
              <Squares2X2Icon className="w-5 h-5" />
            </button>
          </div>
        </div>

        {loading ? (
          <Loading />
        ) : results.length === 0 ? (
          <EmptyState>
            <h2 className="font-bold">There are zero matches. <br /> Use the form to search for People or Movies.</h2>
          </EmptyState>
        ) : (
          <div className="space-y-4">
            <div className={viewMode === 'grid' ? 'grid grid-cols-2 gap-4' : 'space-y-4'}>
              {results.map((item) => (
                <Record
                  key={item.id}
                  item={item}
                  viewMode={viewMode}
                  searchType={searchType}
                  onViewDetails={handleViewDetails}
                />
              ))}
            </div>
            {meta && (
              <Pagination
                currentPage={meta.current_page}
                lastPage={meta.last_page}
                totalResults={meta.total}
                onPageChange={fetchResults}
                loading={loading}
              />
            )}
          </div>
        )}
      </Card>
    </div>
  );
};

export default Search;

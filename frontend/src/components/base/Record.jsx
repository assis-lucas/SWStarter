import PropTypes from 'prop-types';
import Button from './Button';
import { formatDate } from '../../utils/format';

const Record = ({ item, searchType, onViewDetails, viewMode }) => {
  return (
    <div className="border border-gray-300 rounded-lg p-4 hover:shadow-md transition-shadow">
      <div className="flex flex-col gap-4">
        <div className="flex justify-between items-center">
          <h3 className="text-lg font-bold text-gray-900 line-clamp-1">
            {searchType === 'people' ? item.name : item.title}
          </h3>
          {viewMode === 'detailed' && (
            <Button
              onClick={() => onViewDetails(item.id)}
              variant="primary"
              className="w-auto"
            >
              SEE DETAILS
            </Button>
          )}
        </div>
        {searchType === 'people' ? (
          <div className="text-sm text-gray-600 space-y-1">
            <p><span className="font-medium">Gender:</span> {item.gender}</p>
            <p><span className="font-medium">Birth Year:</span> {item.birth_year}</p>
            <p><span className="font-medium">Height:</span> {item.height} cm</p>
          </div>
        ) : (
          <div className="text-sm text-gray-600 space-y-1">
            <p><span className="font-medium">Episode:</span> {item.episode_id}</p>
            <p><span className="font-medium">Director:</span> {item.director}</p>
            <p><span className="font-medium">Release Date:</span> {formatDate(item.release_date)}</p>
            <p className="line-clamp-2"><span className="font-medium">Opening:</span> {item.opening_crawl}</p>
          </div>
        )}
        {viewMode === 'grid' && (
          <Button
            onClick={() => onViewDetails(item.id)}
            variant="primary"
            className="w-auto"
          >
            SEE DETAILS
          </Button>
        )}
      </div>
    </div>
  );
};

Record.propTypes = {
  item: PropTypes.object.isRequired,
  searchType: PropTypes.string.isRequired,
  onViewDetails: PropTypes.func.isRequired,
  viewMode: PropTypes.string,
};

export default Record;

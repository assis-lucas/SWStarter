import PropTypes from 'prop-types';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/react/24/outline';

const Pagination = ({ currentPage, lastPage, totalResults, onPageChange, loading = false }) => {
  const getPageNumbers = () => {
    const pages = [];
    const maxPagesToShow = 5;

    if (lastPage <= maxPagesToShow) {
      for (let i = 1; i <= lastPage; i++) {
        pages.push(i);
      }
    } else {
      pages.push(1);

      let start = Math.max(2, currentPage - 1);
      let end = Math.min(lastPage - 1, currentPage + 1);

      if (currentPage <= 3) {
        start = 2;
        end = 4;
      }

      if (currentPage >= lastPage - 2) {
        start = lastPage - 3;
        end = lastPage - 1;
      }

      if (start > 2) {
        pages.push('...');
      }

      for (let i = start; i <= end; i++) {
        pages.push(i);
      }

      if (end < lastPage - 1) {
        pages.push('...');
      }

      pages.push(lastPage);
    }

    return pages;
  };

  if (lastPage <= 1) {
    return null;
  }

  const pageNumbers = getPageNumbers();

  return (
    <div className="space-y-4">
      <div className="text-sm text-gray-600 text-center">
        Showing page <span className="font-semibold">{currentPage}</span> of{' '}
        <span className="font-semibold">{lastPage}</span>
        {totalResults && (
          <span className="ml-2">
            ({totalResults} total result{totalResults !== 1 ? 's' : ''})
          </span>
        )}
      </div>

      <div className="flex items-center justify-center gap-1">
        <button
          onClick={() => onPageChange(currentPage - 1)}
          disabled={currentPage === 1 || loading}
          className="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          aria-label="Previous page"
        >
          <ChevronLeftIcon className="w-5 h-5" />
        </button>

        {pageNumbers.map((page, index) => (
          page === '...' ? (
            <span key={`ellipsis-${index}`} className="w-10 h-10 flex items-center justify-center text-gray-500">
              ...
            </span>
          ) : (
            <button
              key={page}
              onClick={() => onPageChange(page)}
              disabled={loading}
              className={`w-10 h-10 flex items-center justify-center rounded-lg border transition-colors ${currentPage === page
                ? 'bg-primary-600 text-white border-primary-600'
                : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed'
                }`}
            >
              {page}
            </button>
          )
        ))}

        <button
          onClick={() => onPageChange(currentPage + 1)}
          disabled={currentPage === lastPage || loading}
          className="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          aria-label="Next page"
        >
          <ChevronRightIcon className="w-5 h-5" />
        </button>
      </div>

      {lastPage > 10 && (
        <div className="flex items-center justify-center gap-2 text-sm">
          <label htmlFor="jumpToPage" className="text-gray-600">
            Jump to page:
          </label>
          <input
            id="jumpToPage"
            type="number"
            min="1"
            max={lastPage}
            defaultValue={currentPage}
            onKeyDown={(e) => {
              if (e.key === 'Enter') {
                const page = parseInt(e.target.value);
                if (page >= 1 && page <= lastPage) {
                  onPageChange(page);
                }
              }
            }}
            className="w-20 px-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none"
            disabled={loading}
          />
        </div>
      )}
    </div>
  );
};

Pagination.propTypes = {
  currentPage: PropTypes.number.isRequired,
  lastPage: PropTypes.number.isRequired,
  totalResults: PropTypes.number,
  onPageChange: PropTypes.func.isRequired,
  loading: PropTypes.bool,
};

export default Pagination;

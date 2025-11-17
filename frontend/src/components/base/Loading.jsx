import PropTypes from 'prop-types';

const Loading = ({ message = 'Loading...' }) => {
  return (
    <div className="flex-1 flex items-center justify-center min-h-[400px]">
      <div className="text-center">
        <div className="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
        <p className="mt-4 text-gray-600 font-medium">{message}</p>
      </div>
    </div>
  );
};

Loading.propTypes = {
  message: PropTypes.string,
};

export default Loading;

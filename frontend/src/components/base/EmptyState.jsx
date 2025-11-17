import PropTypes from 'prop-types';

const EmptyState = ({ children }) => {
  return (
    <div className="flex-1 flex items-center justify-center min-h-[400px]">
      <div className="text-center text-gray-500">
        {children}
      </div>
    </div>
  );
};

EmptyState.propTypes = {
  children: PropTypes.node.isRequired,
};

export default EmptyState;

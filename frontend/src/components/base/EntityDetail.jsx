import PropTypes from 'prop-types';

const EntityDetail = ({ title, items, renderItem, onItemClick }) => {
  if (!items || items.length === 0) {
    return null;
  }

  return (
    <div className="mb-6 sm:mb-8 border-t border-gray-300 pt-4">
      <h2 className="text-lg sm:text-xl font-bold text-gray-900 mb-4">{title}</h2>
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        {items.map((item) => renderItem(item, onItemClick))}
      </div>
    </div>
  );
};

EntityDetail.propTypes = {
  title: PropTypes.string.isRequired,
  items: PropTypes.array,
  renderItem: PropTypes.func.isRequired,
  onItemClick: PropTypes.func,
};

export default EntityDetail;

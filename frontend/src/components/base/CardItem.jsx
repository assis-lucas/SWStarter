const CardItem = ({ children, className = '', onClick }) => {
  return (
    <div
      className={`bg-white rounded-lg border border-gray-300 p-6 ${className}`}
      onClick={onClick}
    >
      {children}
    </div>
  );
};

export default CardItem;
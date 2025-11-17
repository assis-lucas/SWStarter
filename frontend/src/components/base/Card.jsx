const Card = ({ children, className = '', onClick }) => {
  return (
    <div
      className={`bg-white rounded-lg shadow-md border border-gray-300 p-6 ${className}`}
      onClick={onClick}
    >
      {children}
    </div>
  );
};

export default Card;
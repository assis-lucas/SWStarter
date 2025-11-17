const Input = ({
  type = 'text',
  placeholder,
  value,
  onChange,
  className = '',
  name,
  ...props
}) => {
  return (
    <input
      type={type}
      placeholder={placeholder}
      value={value}
      onChange={onChange}
      name={name}
      className={`w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-gray-600 focus:border-transparent outline-none transition-all ${className}`}
      {...props}
    />
  );
};

export default Input;

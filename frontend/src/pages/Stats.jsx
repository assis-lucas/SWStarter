import { useState, useEffect } from 'react';
import { ArrowTrendingUpIcon, ClockIcon, BoltIcon, ChartBarIcon } from '@heroicons/react/24/outline';
import api from '../services/api';
import Card from '../components/base/Card';
import Loading from '../components/base/Loading';
import EmptyState from '../components/base/EmptyState';

const StatCard = ({ title, value, subtitle, icon: Icon, color = 'blue' }) => {
  const colorClasses = {
    blue: 'bg-blue-50 text-blue-600',
    green: 'bg-green-50 text-green-600',
    yellow: 'bg-yellow-50 text-yellow-600',
    red: 'bg-red-50 text-red-600',
    purple: 'bg-purple-50 text-purple-600',
  };

  return (
    <Card className="hover:shadow-lg transition-shadow">
      <div className="flex items-start justify-between">
        <div>
          <p className="text-sm font-medium text-gray-600 mb-1">{title}</p>
          <p className="text-3xl font-bold text-gray-900">{value}</p>
          {subtitle && <p className="text-sm text-gray-500 mt-1">{subtitle}</p>}
        </div>
        {Icon && (
          <div className={`p-3 rounded-lg ${colorClasses[color]}`}>
            <Icon className="w-6 h-6" />
          </div>
        )}
      </div>
    </Card>
  );
};

const Stats = () => {
  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchStats();
  }, []);

  const fetchStats = async () => {
    setLoading(true);
    try {
      const response = await api.get('/stats');
      setStats(response.data);
    } catch (error) {
      console.error('Error fetching stats:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <Card className="flex flex-col">
        <Loading message="Loading statistics..." />
      </Card>
    );
  }

  if (!stats) {
    return (
      <Card className="flex flex-col">
        <EmptyState>
          <h2 className="font-bold">No statistics available</h2>
        </EmptyState>
      </Card>
    );
  }

  const maxHourQueries = Math.max(...stats.popular_hours.map(h => h.total));
  const performanceTotal = stats.query_performance.fast + stats.query_performance.medium + stats.query_performance.slow;

  return (
    <div className="space-y-4 sm:space-y-6">
      <div>
        <h1 className="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Database Statistics</h1>
        <p className="text-sm sm:text-base text-gray-600">Real-time insights into your application performance</p>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <StatCard
          title="Total Queries"
          value={stats.summary.total_queries.toLocaleString()}
          subtitle="All time"
          icon={ChartBarIcon}
          color="blue"
        />
        <StatCard
          title="Average Time"
          value={`${stats.avg_query_time}ms`}
          subtitle="Per query"
          icon={ClockIcon}
          color="purple"
        />
        <StatCard
          title="Fastest Query"
          value={`${stats.summary.fastest_query}ms`}
          subtitle="Best performance"
          icon={BoltIcon}
          color="green"
        />
        <StatCard
          title="Slowest Query"
          value={`${stats.summary.slowest_query}ms`}
          subtitle="Needs optimization"
          icon={ArrowTrendingUpIcon}
          color="red"
        />
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
        <Card>
          <h2 className="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Query Performance Distribution</h2>
          <div className="space-y-4">
            <div>
              <div className="flex justify-between mb-1">
                <span className="text-sm font-medium text-green-600">Fast (&lt; 50ms)</span>
                <span className="text-sm font-semibold text-gray-900">{stats.query_performance.fast}</span>
              </div>
              <div className="w-full bg-gray-200 rounded-full h-2">
                <div
                  className="bg-green-600 h-2 rounded-full transition-all"
                  style={{ width: `${(stats.query_performance.fast / performanceTotal) * 100}%` }}
                />
              </div>
            </div>
            <div>
              <div className="flex justify-between mb-1">
                <span className="text-sm font-medium text-yellow-600">Medium (50-200ms)</span>
                <span className="text-sm font-semibold text-gray-900">{stats.query_performance.medium}</span>
              </div>
              <div className="w-full bg-gray-200 rounded-full h-2">
                <div
                  className="bg-yellow-500 h-2 rounded-full transition-all"
                  style={{ width: `${(stats.query_performance.medium / performanceTotal) * 100}%` }}
                />
              </div>
            </div>
            <div>
              <div className="flex justify-between mb-1">
                <span className="text-sm font-medium text-red-600">Slow (&gt; 200ms)</span>
                <span className="text-sm font-semibold text-gray-900">{stats.query_performance.slow}</span>
              </div>
              <div className="w-full bg-gray-200 rounded-full h-2">
                <div
                  className="bg-red-600 h-2 rounded-full transition-all"
                  style={{ width: `${(stats.query_performance.slow / performanceTotal) * 100}%` }}
                />
              </div>
            </div>
          </div>
        </Card>

        <Card>
          <h2 className="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Activity by Hour</h2>
          <div className="flex items-end justify-between h-32 sm:h-48 gap-1">
            {stats.popular_hours.map((hour) => (
              <div key={hour.hour} className="flex-1 flex flex-col items-center justify-end">
                <div
                  className="w-full bg-primary-600 rounded-t hover:bg-primary-700 transition-colors cursor-pointer relative group"
                  style={{ height: `${(hour.total / maxHourQueries) * 100}%`, minHeight: '4px' }}
                  title={`${hour.total} queries at ${hour.hour}:00`}
                >
                  <div className="absolute bottom-full mb-2 hidden group-hover:block bg-gray-900 text-white text-xs rounded py-1 px-2 whitespace-nowrap">
                    {hour.total} queries<br/>{hour.avg_time}ms avg
                  </div>
                </div>
                <span className="text-xs text-gray-500 mt-1">{hour.hour}</span>
              </div>
            ))}
          </div>
        </Card>
      </div>

      <Card>
        <h2 className="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Top 10 Most Frequent Queries</h2>
        <div className="overflow-x-auto -mx-4 sm:mx-0">
          <table className="w-full">
            <thead>
              <tr className="border-b border-gray-200">
                <th className="text-left py-2 sm:py-3 px-2 sm:px-4 text-xs sm:text-sm font-semibold text-gray-700">#</th>
                <th className="text-left py-2 sm:py-3 px-2 sm:px-4 text-xs sm:text-sm font-semibold text-gray-700">Query</th>
                <th className="text-right py-2 sm:py-3 px-2 sm:px-4 text-xs sm:text-sm font-semibold text-gray-700">Count</th>
                <th className="text-right py-2 sm:py-3 px-2 sm:px-4 text-xs sm:text-sm font-semibold text-gray-700">Avg Time</th>
              </tr>
            </thead>
            <tbody>
              {stats.top_queries.map((query, index) => (
                <tr key={index} className="border-b border-gray-100 hover:bg-gray-50">
                  <td className="py-2 sm:py-3 px-2 sm:px-4 text-xs sm:text-sm text-gray-500">{index + 1}</td>
                  <td className="py-2 sm:py-3 px-2 sm:px-4 text-xs font-mono max-w-[150px] sm:max-w-none truncate sm:text-gray-900">{query.sql}</td>
                  <td className="py-2 sm:py-3 px-2 sm:px-4 text-xs sm:text-sm text-gray-900 text-right font-semibold">{query.total}</td>
                  <td className="py-2 sm:py-3 px-2 sm:px-4 text-xs sm:text-sm text-right">
                    <span className={`inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${
                      query.avg_time < 50 ? 'bg-green-100 text-green-800' :
                      query.avg_time < 200 ? 'bg-yellow-100 text-yellow-800' :
                      'bg-red-100 text-red-800'
                    }`}>
                      {query.avg_time}ms
                    </span>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </Card>

      <Card>
        <h2 className="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Slowest Queries</h2>
        <div className="space-y-3">
          {stats.slowest_queries.map((query, index) => (
            <div key={index} className="border border-gray-200 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow">
              <div className="flex flex-col sm:flex-row justify-between items-start mb-2 gap-2">
                <code className="text-xs font-mono text-gray-700 flex-1 break-all">{query.sql}</code>
                <span className="px-2 sm:px-3 py-1 bg-red-100 text-red-800 text-xs sm:text-sm font-semibold rounded-full whitespace-nowrap">
                  {query.max_time}ms
                </span>
              </div>
              <p className="text-xs sm:text-sm text-gray-500">Executed {query.count} times</p>
            </div>
          ))}
        </div>
      </Card>

      {stats.recent_activity && stats.recent_activity.length > 0 && (
        <Card>
          <h2 className="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Activity Last 7 Days</h2>
          <div className="grid grid-cols-7 gap-1 sm:gap-2">
            {stats.recent_activity.map((day) => (
              <div key={day.date} className="text-center">
                <div className="text-[10px] sm:text-xs text-gray-500 mb-1">{new Date(day.date).toLocaleDateString('en-US', { weekday: 'short' })}</div>
                <div className="bg-primary-100 rounded-lg py-2 sm:py-3 px-1 sm:px-2">
                  <div className="text-sm sm:text-lg font-bold text-primary-900">{day.total}</div>
                  <div className="text-[10px] sm:text-xs text-primary-700">queries</div>
                </div>
              </div>
            ))}
          </div>
        </Card>
      )}
    </div>
  );
};

export default Stats;

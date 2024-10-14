// public/app.js
import React from 'react';
import ReactDOM from 'react-dom';

function NewsFeed() {
    const [news, setNews] = React.useState([]);

    React.useEffect(() => {
        // Fetch Tischtennis-News (Fake API für das Beispiel)
        fetch('https://example.com/api/tischtennis-news')
            .then(response => response.json())
            .then(data => setNews(data));
    }, []);

    return (
        <div>
            <h2>Aktuelle Tischtennis-News</h2>
            <ul>
                {news.map((item, index) => (
                    <li key={index}>{item.title}</li>
                ))}
            </ul>
        </div>
    );
}

ReactDOM.render(<NewsFeed />, document.getElementById('news'));

const cloudName = 'djpfl2uwe';
const apiKey = '488785883143157';
const apiSecret = 'qq5kq2-5R9aN4SNi8olLgh-Bb9A';

async function listResources(folder = 'lotsha-web/yearbook/years/2023/Grade 1A') {
    // Search API is POST
    const url = `https://api.cloudinary.com/v1_1/${cloudName}/resources/search`;
    const auth = Buffer.from(`${apiKey}:${apiSecret}`).toString('base64');
    
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Basic ${auth}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                expression: `folder:"${folder}"`,
                max_results: 100
            })
        });
        const data = await response.json();
        console.log(JSON.stringify(data, null, 2));
    } catch (error) {
        console.error('Error fetching resources:', error);
    }
}

listResources();

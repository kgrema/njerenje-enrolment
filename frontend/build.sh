#!/bin/bash
# Save as frontend/build.sh
echo "Building frontend for Render..."

# Create a simple index.html if it doesn't exist
if [ ! -f "index.html" ]; then
    echo "Error: index.html not found!"
    exit 1
fi

# Create a basic nginx config for static serving
cat > nginx.conf << 'EOF'
events {
    worker_connections 1024;
}

http {
    server {
        listen ${PORT};
        root /opt/render/project/src;
        index index.html;
        
        location / {
            try_files $uri $uri/ /index.html;
        }
        
        # API proxy (if needed)
        location /api/ {
            proxy_pass https://njerenje-api.onrender.com;
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
        }
    }
}
EOF

echo "Frontend build complete!"
<footer>
    <p>&copy; 2024 Yrgopelago</p>
</footer>

<script>
    // Pass PHP environment variables to JavaScript
    window.apiKey = <?php echo json_encode($_ENV['api_key'] ?? 'empty'); ?>;
</script>
<script src="./app/app.js"></script>
<script src="./app/api/api.js"></script>
</body>

</html>
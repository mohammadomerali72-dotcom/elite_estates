<?php 
session_start();
include 'includes/db.php'; 

// 1. Search Logic
$search_query = "";
if(isset($_GET['search']) && !empty($_GET['search'])) {
    $s = mysqli_real_escape_string($conn, $_GET['search']);
    $search_query = " WHERE location LIKE '%$s%' OR title LIKE '%$s%' ";
}

// 2. Fetch Properties
$query = "SELECT * FROM properties" . $search_query . " ORDER BY id DESC";
$result = mysqli_query($conn, $query);

// 3. Fetch Notifications count for logged-in user
$notif_count = 0;
if(isset($_SESSION['user_id'])) {
    $u_id = $_SESSION['user_id'];
    $notif_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM notifications WHERE user_id = '$u_id' AND is_read = 0");
    if($notif_res) {
        $notif_data = mysqli_fetch_assoc($notif_res);
        $notif_count = $notif_data['total'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EliteEstates | Premium 3D Luxury Real Estate</title>
    
    <!-- External CSS & Fonts -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">

    <!-- Essential Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/mrdoob/three.js@r128/examples/js/loaders/GLTFLoader.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.7.0/vanilla-tilt.min.js"></script>
    <script src='https://meet.jit.si/external_api.js'></script> <!-- Video Call API -->
</head>
<body>

<!-- Dynamic Navigation -->
<nav>
    <div class="logo">
        <a href="index.php"><img src="assets/img/logo.png" height="50" alt="EliteEstates"></a>
    </div>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="#properties">Properties</a></li>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <!-- User Actions -->
            <li style="position:relative; cursor:pointer;">🔔 <span class="notif-badge"><?php echo $notif_count; ?></span></li>
            <li style="color:var(--accent); font-weight:700;">Hi, <?php echo explode(' ', $_SESSION['user_name'])[0]; ?></li>
            
            <?php if($_SESSION['user_role'] == 'provider'): ?>
                <li><a href="provider_dashboard.php" class="btn-portal">Seller Portal</a></li>
            <?php endif; ?>
            
            <li><a href="logout.php" style="color:#ff4d4d; font-weight:600;">Logout</a></li>
        <?php else: ?>
            <!-- Guest Links -->
            <li><a href="login.php">Sign In</a></li>
            <li><a href="signup.php" class="btn-portal">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>

<!-- 3D Hero Section -->
<section class="hero">
    <canvas id="hero-canvas"></canvas>
    <div class="hero-content">
        <p style="color:var(--accent); letter-spacing:8px; font-weight:600; font-size:12px; text-transform:uppercase;">Next Dimension Living</p>
        <h2>Experience The<br>Future of Home</h2>
        <div class="search-box">
            <form action="index.php" method="GET" style="display:flex; width:100%;">
                <input type="text" name="search" placeholder="Search by location (e.g. DHA, Gulberg)" value="<?php echo @$_GET['search']; ?>">
                <button type="submit">Explore</button>
            </form>
        </div>
    </div>
</section>

<!-- Properties Section -->
<div class="container" id="properties">
    <div style="margin-bottom: 60px;">
        <p style="color:var(--accent); font-weight:600; letter-spacing:2px;">HANDPICKED SELECTION</p>
        <h2 style="font-size: 48px; font-weight: 800;">Featured Listings</h2>
    </div>

    <div class="grid">
        <?php 
        if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $type_badge = ($row['property_type'] == 'Rent') ? 'badge-rent' : 'badge-sale';
        ?>
            <!-- Single Card -->
            <div class="card" data-tilt data-tilt-max="5">
                <div class="card-img">
                    <span class="badge <?php echo $type_badge; ?>">For <?php echo $row['property_type']; ?></span>
                    <img src="uploads/property_main/<?php echo $row['image_main']; ?>" alt="Property">
                </div>
                <div class="card-body" style="padding:25px 10px;">
                    <span style="color:var(--accent); font-size:26px; font-weight:800;"><?php echo $row['price']; ?></span>
                    <h3 style="color:white; margin:10px 0;"><?php echo $row['title']; ?></h3>
                    <p style="color:#666;">📍 <?php echo $row['location']; ?></p>
                    <a href="property_details.php?id=<?php echo $row['id']; ?>" class="btn-view">View 360° Tour</a>
                </div>
            </div>
        <?php 
            }
        } else {
            echo "<p style='grid-column:1/-1; text-align:center; opacity:0.5; padding:100px;'>No listings found in this location.</p>";
        }
        ?>
    </div>
</div>

<!-- Elite Messenger Widget -->
<div class="chat-btn" onclick="toggleChat()">💬</div>
<div class="chat-box" id="chatBox" style="display:none; flex-direction:column; position:fixed; bottom:100px; right:30px; width:350px; height:500px; background:#0b0e14; border:1px solid var(--border); border-radius:30px; z-index:2000; overflow:hidden;">
    <div style="background:var(--accent); padding:20px; color:black; font-weight:800; display:flex; justify-content:space-between;">
        <span>Elite Support</span>
        <span onclick="startVideoCall()" style="cursor:pointer">📹 Call Agent</span>
    </div>
    <div id="chatMessages" style="flex:1; padding:20px; overflow-y:auto; font-size:13px; color:#ccc;">
        <p style="text-align:center; opacity:0.5;">Hi! Welcome to EliteEstates.</p>
    </div>
    <div style="padding:15px; display:flex; gap:10px; border-top:1px solid var(--border);">
        <input type="text" id="chatInput" placeholder="Type here..." style="flex:1; background:transparent; border:none; color:white; outline:none;">
        <button onclick="sendChatMessage()" style="background:var(--accent); border:none; padding:8px 15px; border-radius:10px; cursor:pointer; font-weight:700;">Send</button>
    </div>
</div>

<!-- Video Call Frame (Jitsi) -->
<div id="videoOverlay" style="position:fixed; top:0; left:0; width:100%; height:100%; z-index:3000; background:black; display:none;">
    <button onclick="closeVideoCall()" style="position:absolute; top:20px; right:20px; z-index:3100; background:red; color:white; border:none; padding:10px 20px; border-radius:50px; cursor:pointer;">End Call</button>
    <div id="meet" style="height:100%"></div>
</div>

<script>
    // --- 1. THREE.JS 3D ENGINE ---
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ canvas: document.getElementById('hero-canvas'), alpha: true, antialias: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(window.devicePixelRatio);

    scene.add(new THREE.AmbientLight(0xffffff, 2));
    const dLight = new THREE.DirectionalLight(0xffffff, 2.5); dLight.position.set(5, 15, 10); scene.add(dLight);
    camera.position.z = 10;
    camera.position.y = 1.5;

    let house;
    new THREE.GLTFLoader().load('assets/models/house.glb', (gltf) => {
        house = gltf.scene;
        const box = new THREE.Box3().setFromObject(house);
        const size = box.getSize(new THREE.Vector3());
        const scale = 11 / Math.max(size.x, size.y, size.z);
        house.scale.set(scale, scale, scale);
        house.position.y = -3;
        scene.add(house);
    }, undefined, (e) => {
        // Sphere Fallback
        const geo = new THREE.SphereGeometry(3.5, 64, 64);
        const mat = new THREE.MeshStandardMaterial({ color: 0xd4af37, wireframe: true });
        house = new THREE.Mesh(geo, mat); scene.add(house);
    });

    function animate() {
        requestAnimationFrame(animate);
        if(house) house.rotation.y += 0.003;
        renderer.render(scene, camera);
    }
    animate();

    window.addEventListener('mousemove', (e) => {
        if(house) {
            const x = (e.clientX / window.innerWidth) - 0.5;
            const y = (e.clientY / window.innerHeight) - 0.5;
            gsap.to(house.rotation, { y: x * 1.8, x: y * 0.4, duration: 1.5 });
        }
    });

    // --- 2. COMMUNICATION FUNCTIONS ---
    function toggleChat() {
        const box = document.getElementById('chatBox');
        box.style.display = (box.style.display === 'flex') ? 'none' : 'flex';
    }

    let jitsiApi = null;
    function startVideoCall() {
        document.getElementById('videoOverlay').style.display = 'block';
        const options = {
            roomName: 'EliteEstates_Premium_Room_<?php echo @$_SESSION["user_id"]; ?>',
            parentNode: document.querySelector('#meet'),
            userInfo: { displayName: '<?php echo @$_SESSION["user_name"]; ?>' }
        };
        jitsiApi = new JitsiMeetExternalAPI("meet.jit.si", options);
    }
    function closeVideoCall() {
        if(jitsiApi) jitsiApi.dispose();
        document.getElementById('videoOverlay').style.display = 'none';
    }

    // --- 3. GSAP SCROLL REVEAL ---
    gsap.registerPlugin(ScrollTrigger);
    gsap.from(".card", {
        scrollTrigger: { trigger: ".grid", start: "top 85%" },
        y: 80, opacity: 0, duration: 1, stagger: 0.15, ease: "power4.out"
    });

    window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });
</script>

<?php include 'includes/footer.php'; ?>

</body>
</html>
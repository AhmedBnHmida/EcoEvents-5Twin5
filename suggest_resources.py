import json
import sys

if len(sys.argv) != 3:
    print("Usage: python suggest_resources.py <categorie_id> <capacity_max>")
    sys.exit(1)

categorie_id = int(sys.argv[1])
capacity_max = int(sys.argv[2])

debug_mode = len(sys.argv) > 3 and sys.argv[3] == 'quiet'  # No debug if 'quiet' arg

if not debug_mode:
    print(f"DEBUG: Looking for category {categorie_id} with capacity {capacity_max}")

json_path = 'storage/app/events_history.json'
try:
    with open(json_path, encoding='utf-8') as f:
        events = json.load(f)
except FileNotFoundError:
    if not debug_mode:
        print("DEBUG: JSON file not found - using defaults")
    events = []

if not debug_mode and events:
    print(f"DEBUG: Total events loaded: {len(events)}")

# First, try to find similar events by category and capacity (±25%)
similar_events = [
    event for event in events
    if event.get('categorie_id') == categorie_id and
       abs(event.get('capacity_max', 0) - capacity_max) <= capacity_max * 0.25
]

if not debug_mode:
    print(f"DEBUG: Similar events by capacity (±25%): {len(similar_events)}")

# If no similar events by capacity, fall back to all events in the same category
if not similar_events:
    similar_events = [
        event for event in events
        if event.get('categorie_id') == categorie_id
    ]
    if not debug_mode:
        print(f"DEBUG: Fallback - All events in category: {len(similar_events)}")

if not debug_mode:
    print(f"DEBUG: Processing {len(similar_events)} events for resources...")

resource_sums = {}
resource_counts = {}
representative_noms = {}  # To store a descriptive 'nom' for each type
for i, event in enumerate(similar_events):
    # Try both 'ressources' and 'resources' keys
    ressources = event.get('ressources', event.get('resources', []))
    if not debug_mode:
        print(f"DEBUG: Event {i+1} (ID {event.get('id')}) resources array length: {len(ressources)}")
    
    event_resources = {}
    for res in ressources:
        nom = res.get('nom', '')  # Get the descriptive name, e.g., "qqqqqqqqqq"
        res_type = res.get('type', '')
        if res_type and nom:  # Require both for valid resource
            qty = int(res.get('quantite', 1))
            key = res_type  # Group by type, but use nom for display
            event_resources[key] = event_resources.get(key, 0) + qty
            # Logic for representative nom: prefer non-"Test" names, longest among them
            current_nom = representative_noms.get(key, '')
            if not current_nom:
                representative_noms[key] = nom
            elif current_nom == 'Test' and nom != 'Test':
                representative_noms[key] = nom
            elif nom != 'Test' and current_nom != 'Test':
                if len(nom) > len(current_nom):
                    representative_noms[key] = nom
            elif nom == 'Test' and current_nom != 'Test':
                pass  # keep non-Test
            elif len(nom) > len(current_nom):  # both Test, longer
                representative_noms[key] = nom
    if not debug_mode:
        print(f"DEBUG: Event {i+1} aggregated by type: {event_resources}")
    
    for res_type, event_qty in event_resources.items():
        resource_sums[res_type] = resource_sums.get(res_type, 0) + event_qty
        resource_counts[res_type] = resource_counts.get(res_type, 0) + 1

if not debug_mode:
    print(f"DEBUG: Resource types found: {list(resource_sums.keys())}")
    print(f"DEBUG: Representative noms per type: {representative_noms}")

suggestions = []
for res_type, total_qty in resource_sums.items():
    avg_qty = int(total_qty / resource_counts[res_type])
    if avg_qty > 0:
        rep_nom = representative_noms.get(res_type, res_type)  # Use descriptive nom like "qqqqqqqqqq"
        suggestions.append({
            "nom": rep_nom,
            "type": res_type,
            "quantite": avg_qty
        })
        if not debug_mode:
            print(f"DEBUG: Suggesting {avg_qty} of '{rep_nom}' (type: {res_type})")

if not debug_mode:
    print(f"DEBUG: Total suggestions generated: {len(suggestions)}")

# Only if no events in the category at all, use hardcoded defaults
if not suggestions:
    if not debug_mode:
        print("DEBUG: No suggestions from data - using defaults")
    suggestions = [
        {"nom": "Chaise", "type": "Chaise", "quantite": capacity_max},
        {"nom": "Table", "type": "Table", "quantite": max(1, capacity_max // 10)}
    ]

print(json.dumps({"resources": suggestions}, indent=2, ensure_ascii=False))
import json
import sys
import os

if len(sys.argv) != 3:
    print(json.dumps({"error": "Usage: python suggest_ressources.py <categorie_id> <capacity_max>"}), file=sys.stderr)
    sys.exit(1)

try:
    categorie_id = int(sys.argv[1])
    capacity_max = int(sys.argv[2])

    # Chemin absolu relatif au script: depuis app/Http/Scripts/ -> ../../storage/app/
    script_dir = os.path.dirname(os.path.abspath(__file__))
    history_path = os.path.join(script_dir, '../../storage/app/events_history.json')
    history_path = os.path.abspath(history_path)  # Normalise le chemin

    with open(history_path, encoding='utf-8') as f:
        events = json.load(f)

    similar_events = [
        event for event in events
        if event.get('categorie_id') == categorie_id and
           abs(event.get('capacity_max', 0) - capacity_max) <= capacity_max * 0.25
    ]

    resource_sums = {}
    resource_counts = {}
    for event in similar_events:
        for res in event.get('ressources', []):
            res_type = res.get('type')
            qty = int(res.get('quantite', 1))
            resource_sums[res_type] = resource_sums.get(res_type, 0) + qty
            resource_counts[res_type] = resource_counts.get(res_type, 0) + 1

    suggestions = []
    for res_type, total_qty in resource_sums.items():
        avg_qty = int(total_qty / resource_counts[res_type])
        suggestions.append({
            "nom": res_type,
            "type": res_type,
            "quantite": avg_qty
        })

    if not suggestions:
        suggestions = [
            {"nom": "Chaise", "type": "Chaise", "quantite": capacity_max},
            {"nom": "Table", "type": "Table", "quantite": max(1, capacity_max // 10)}
        ]

    print(json.dumps({"resources": suggestions}, indent=2, ensure_ascii=False))

except FileNotFoundError as e:
    # Fallback si history manquant/vide
    print(json.dumps({"resources": [
        {"nom": "Chaise", "type": "Chaise", "quantite": capacity_max},
        {"nom": "Table", "type": "Table", "quantite": max(1, capacity_max // 10)}
    ]}))
except Exception as e:
    print(json.dumps({"error": str(e)}))
    sys.exit(1)
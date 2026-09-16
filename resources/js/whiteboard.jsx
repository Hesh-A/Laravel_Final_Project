import { createRoot } from 'react-dom/client';
import { Excalidraw } from '@excalidraw/excalidraw';
import { useState, useCallback } from 'react';
import '@excalidraw/excalidraw/index.css';

function Whiteboard({ initialDrawing, canEdit, saveUrl }) {


    const [drawingData, setDrawingData] = useState(() => (
    initialDrawing ?? {
        elements: [],
        appState: {},
        files: {},
    }
    ));
    const [saveStatus, setSaveStatus] = useState('idle');
    const [theme, setTheme] = useState('light');

    const handleChange = useCallback((elements, appState, files) => {
        setDrawingData({
            elements,
            appState,
            files,
        });

        setSaveStatus('unsaved');
    }, []);

    async function handleSave() {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content');

        setSaveStatus('saving');

        try {
            const response = await fetch(saveUrl, {
                method: 'PATCH',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    drawing_data: drawingData,
                }),
            });

            if (!response.ok) {
                throw new Error('Could not save the whiteboard.');
            }

            setSaveStatus('saved');
        } catch (error) {
            setSaveStatus('error');
        }
    }



    return (

       <section className="space-y-3">
            {/* <div className="flex items-center gap-2 justify-between">    
                <select value={theme} onChange={(e) => setTheme(e.target.value)}>
                    <option value="light">Light</option>
                    <option value="dark">Dark</option>
                </select>
            </div> */}
            <div className="h-dvh overflow-hidden border border-gray-500">
                <Excalidraw
                    initialData={initialDrawing}
                    viewModeEnabled={!canEdit}
                    onChange={handleChange}
                    theme='dark'
                />
            </div>

            {canEdit && (
                <div className="flex items-center justify-end gap-3">
                    {saveStatus === 'unsaved' && (
                        <span className="text-sm text-gray-400">Unsaved changes</span>
                    )}

                    {saveStatus === 'saving' && (
                        <span className="text-sm text-gray-400">Saving...</span>
                    )}

                    {saveStatus === 'saved' && (
                        <span className="text-sm text-green-500">Saved</span>
                    )}

                    {saveStatus === 'error' && (
                        <span className="text-sm text-red-500">Could not save. Try again.</span>
                    )}

                    <button
                        type="button"
                        className="btn"
                        onClick={handleSave}
                        disabled={saveStatus === 'saving'}
                    >
                        Save Whiteboard
                    </button>
                </div>
            )}
            
        </section>


    );



}

document.querySelectorAll('[data-whiteboard]').forEach((element) => {

    const initialDrawing = element.dataset.initialDrawing === 'null'
        ? null
    : JSON.parse(element.dataset.initialDrawing);

    const canEdit = element.dataset.canEdit === 'true';
    const root = createRoot(element);

    root.render(<Whiteboard 
                initialDrawing={initialDrawing}
            canEdit={canEdit}
            saveUrl={element.dataset.saveUrl}
    />);
});

